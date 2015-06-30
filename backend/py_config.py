
import logging
import MySQLdb
import re 
import datetime
import os

class Logger:
	@staticmethod
	def setup_log():
		logfile = r"E:\Webserver\xampp\htdocs\citation\backend\data.log"
		'''logging.basicConfig(filename=logfile,level=logging.DEBUG,
							format='%(asctime)s. %(levelname)s: %(message)s', 
							datefmt='%m/%d/%Y %I:%M:%S %p') '''
		logging.basicConfig(filename=logfile,level=logging.DEBUG,
					format='%(message)s')

class DbAccess:
	
	dbname = 'citation_network';
	hostname = 'localhost'; 
	username = 'root'; 
	password = '';
	db = MySQLdb.connect(hostname,username,password,dbname) 
	
	def execute(self, statement):     
		cursor = self.db.cursor() 
		sql = statement
		try: 
			res = cursor.execute(sql)
			cursor.close()
			self.commit()   
			return res
		except ValueError:
			print ValueError  
			return -1 
	
	def execute_insert(self, statement):     
		cursor = self.db.cursor() 
		sql = statement
		try: 
			res = cursor.execute(sql)  
			self.db.commit() 
			return res
		except:  
			return -1 
		
	def execute_select(self, statement):     
		cursor = self.db.cursor() 
		sql = statement
		try: 
			cursor.execute(sql)   
			return cursor.fetchall()
		except:  
			return -1 
	
	def commit(self):
		self.db.commit()
		
	def rollback(self):
		self.db.rollback()
	
class Parser:  
	db = DbAccess() 

	def export_file_to_db(self, filePath):
		logging.info("File to Db: "+filePath)  
		fileName = os.path.basename(filePath)
		tableName = os.path.splitext(fileName)[0] 
		
		timeInfo = self.create_tables(tableName)
		if(timeInfo != None):
			lineType = ""
			f = open(filePath, "r")
			for line in f:
				if("*vertices" in line.lower()):
					lineType="vertices"
					continue
				elif("*arcs" in line.lower()):
					lineType="arcs"
					continue
				
				if(lineType=="vertices"):
					res = self.insert_citation(line, timeInfo) 
				elif(lineType=="arcs"):
					res = self.insert_edge(line, timeInfo)  
			self.db.commit()
	
	def insert_citation(self, str, timeInfo):
		data = str.split('"')
		id_citation = data[0]
		title = data[1].split('|')[0]
		year = data[1].split('|')[1]
		
		sql	="INSERT INTO citations_"+timeInfo+ "(id_citation, title, year) VALUES ("+id_citation+",'"+title+"','"+year+"')"
		self.db.execute(sql)     

	def insert_edge(self, str, timeInfo):
		data = str.split(' ')
		source = data[0]
		destination = data[1] 
		
		sql	="INSERT INTO edges_"+timeInfo+ "(source, destination) VALUES ('"+source+"','"+destination+"')"
		self.db.execute(sql)
	
	def create_tables(self, tableName):
		timeInfo = self.get_time_info(tableName)
		tb1 = "citations_"+timeInfo
		tb2 = "edges_"+timeInfo 
			
		sql	=""" CREATE TABLE IF NOT EXISTS """+tb1+""" (
				`id_citation` int(11) NOT NULL,
				`title` text DEFAULT NULL,
				`author` varchar(45) DEFAULT NULL,
				`year` int(4) DEFAULT NULL,
				`ieee_id` varchar(45) DEFAULT NULL,
				PRIMARY KEY (`id_citation`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	
				CREATE TABLE IF NOT EXISTS """+tb2+""" (
				`source` int(11) DEFAULT NULL,
				`destination` int(11) DEFAULT NULL,
				`weight` float DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1; """
		
		res = self.db.execute(sql) 
		if(res == -1): 
			logging.info("Create Table: "+tb1+" and "+tb2+" failed, possibly table exist")  
		else:
			logging.info("Create Table: "+tb1+" and "+tb2+" success") 
		return timeInfo

	def get_time_info(self, tableName):
		timeInfo = ""
		today = datetime.datetime.now()
		if(tableName==None):
			return today.strftime('%H_%M_%d_%m_%y') 
		else:
			timeInfo = tableName.split('_', 1); 
			if(len(timeInfo) < 2):
				return today.strftime('%H_%M_%d_%m_%y')
			elif(len(timeInfo[1].split('_')) != 5):
				return today.strftime('%H_%M_%d_%m_%y')
			else:
				return timeInfo[1]

class Crawler:
	lowestYear = 1999
	highestYear = 2014
	def get_years(self, title, lbound=lowestYear, hbound=highestYear):
		pattern = r"(19[689]\d|20[01]\d)"
		matches = re.finditer(pattern, title)
		years = list()
		for m in matches:  
			if int(lbound) <= int(m.group(0)) <= int(hbound) :
				if not m.group(0) in years:
					years.append(m.group(0)) 
		return years
	 