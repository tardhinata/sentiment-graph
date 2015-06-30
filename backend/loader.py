import sys, getopt 
import json
from py_config import *
from web_parser import *

parser = Parser()
web_parser = WebParser()
Logger.setup_log()

def main(argv): 
	argument = '' 
	try:
		opts, args = getopt.getopt(argv,"hd:f:p:",["help","dbtofile=","filetodb=","parsetabledetails="])
	except getopt.GetoptError:
		print('Argument error, check -h | --help')
		sys.exit(2)
	
	for opt, arg in opts: 
		if opt in ("-h", "--help"):
			help()
		elif opt in ("-d", "--dbtofile"):
			argument = arg
			export_db_to_file(argument)
		elif opt in ("-f", "--filetodb"):
			argument = arg
			export_file_to_db(argument)
		elif opt in ("-p", "--parsetabledetails"):
			argument = arg
			parse_table_details(argument)
		else:
			help()
			sys.exit()

def help():
	print('Usage:')
	print('loader.py --dbtofile <tableName> --filetodb <filePath> --parsetabledetails <table_name>')
	print('loader.py -d <tableName> -f <filePath> -p <table_name>') 
	sys.exit()

def export_db_to_file(tableName):
	print("db to file: "+tableName)
	
def export_file_to_db(filePath):
	parser.export_file_to_db(filePath)

def parse_table_details(table_name):
	web_parser.get_table_details(table_name)

if __name__ == "__main__":
	
	if(len(sys.argv[1:]) == 0):
		print('Argument error, check -h | --help')
	else:
		main(sys.argv[1:])
	