import urllib
import lxml.html
import re  
from py_config import * 
import threading
import Queue
import time

class WebParser:
    db = DbAccess() 
    Logger.setup_log()
    
    def get_http_doc(self, url):
        url = "http://ieeexplore.ieee.org/search/searchresult.jsp?newsearch=true&queryText="+url 
        doc = urllib.urlopen(url).read()   
        return doc
    
    def get_arr_number(self, string): 
        pattern = r"(?s)(?<=arnumber=).+?(?=&query)" 
        matches = re.search(pattern, string)
        if matches:
            return matches.group(0)
        else:
            return "-"
    
    def get_ieee_details(self, table_name, id, title, queue, count=0): 
        count+=1
        if(count<=10):
            try: 
                doc = lxml.html.document_fromstring(self.get_http_doc(title)) 
                
                el_id = doc.xpath("//ul[@class='Results'][1]/li[1]/div[1]/div[@class='detail']/h3/a")
                ieee_id = self.get_arr_number(el_id[0].attrib['href']) 
                
                authors=""
                el_authors = doc.xpath("//ul[@class='Results'][1]/li[1]/div[1]/div[@class='detail']/a/span[@id='preferredName']")
                for author in el_authors:
                    authors+=author.attrib['class']+"; " 
                
                sql = "update "+table_name+" set author = '"+authors.replace("'", "")+"', ieee_id = '"+ieee_id+"' where id_citation="+ str(id)+"; "
                queue.put(sql)
                logging.info(sql)
                if (int(id)%10==0):
                    print ieee_id + " - " + str(id) 
                #sql= "when "+str(id)+" then '"+ieee_id+"'" SS
                #dbx=DbAccess()
                #dbx.execute(sql) 
                #dbx.commit()
                #print sql 
            except: 
                if(count>1):
                    print "rescue "+str(id) + str(count)
                time.sleep(1) 
                self.get_ieee_details(id, table_name, title, queue,count) 
     
    def get_table_details(self, table_name):
        result = Queue.Queue()
        sql = "select id_citation, title from "+table_name+" where ieee_id is null" 
        res = self.db.execute_select(sql)
        
        threads = [threading.Thread(target=self.get_ieee_details, args = (table_name, row[0], row[1], result)) for row in res] 
        try:
            for t in threads:
                t.start()
                time.sleep(1) 
            for t in threads:
                t.join() 
        except: 
            print "idk"
        
        finalsql = ""
        while not result.empty():
            job = result.get() 
            finalsql += job  
        
        print "EXECUTING"
        logging.info("Update Table: "+finalsql)
        self.db.execute(finalsql)  
        print "FINISH" 