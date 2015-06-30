import re
import string
from DbAccess import *
from nltk.tokenize import WhitespaceTokenizer
import xlsxwriter

class RandomComments:
	_database = '' #database name
	_wordList = '' #path of file that contains words to select random comments
	_saveDir = '' #path to save the data

	def generateRandomComments(self):
		self.db = DbAccess( self._database, 'localhost', 'root', '')

		progress=0
		file = open(self._wordList, 'r')
		lines = file.readlines()
		for line in lines:
			progress+=1
			word = WhitespaceTokenizer().tokenize(line)[0]
			sql	= "SELECT rd.request_id commit_id, rd.author comitter_id, ic.patchset_id, ic.author_id commenter_id, ic.message "
			sql	+= "FROM inline_comments ic, request_detail rd "
			sql	+= "WHERE rd.request_id in "
			sql	+= "(select request_id from (select distinct rd.request_id from request_detail rd, inline_comments ic "
			sql	+= "where ic.request_id=rd.request_id AND ic.message LIKE '%" + word + "%' "
			sql	+= "ORDER BY RAND() LIMIT 0,20) req) "
			sql	+= "AND ic.request_id=rd.request_id AND ic.message LIKE '%""" + word + "%' "
			sql	+= "ORDER BY ic.request_id ;"

			records = self.db.execute_select(sql)
			totalData = len(records)
			#with open(self._saveDir + word + '.csv', 'w') as handle:
			#	for r in records:
			#		handle.writelines(word+'\t'+str(r[0])+'\t'+str(r[4]))

			# Create an new Excel file and add a worksheet.
			workbook = xlsxwriter.Workbook(self._saveDir + word + '.xlsx')
			bold = workbook.add_format({'bold': True})
			worksheet = workbook.add_worksheet()
			# Widen the first column to make the text clearer.
			worksheet.set_column('A:A', 20)
			worksheet.set_column('B:B', 200)
			worksheet.write('A1', 'Commit ID', bold)
			worksheet.write('B1', 'Comments', bold)

			# Write commit comments
			row = 1
			for r in records:
				row += 1
				worksheet.write('A'+str(row), unicode(str(r[0]), errors='ignore'))
				worksheet.write('B'+str(row), unicode(str(r[4]), errors='ignore'))
			workbook.close()

			print str(progress) + ' of ' + str(len(lines)) + ' words'

if __name__ == "__main__":
	while True:
		input = raw_input("Press enter to start:").split(' ', 1 )
		rc = RandomComments()
		rc._database = 'gerrit_ovirt'
		rc._wordList = 'WordList.txt'
		rc._saveDir = 'Result/'
		#Generate random comments from gerrit_ovirt, table inline_comments
		dict = rc.generateRandomComments()
