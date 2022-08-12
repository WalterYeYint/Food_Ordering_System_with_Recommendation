import mysql.connector as connection
import pandas as pd

# # Accessing data using cursor() method
# mydb = connection.connect(host="localhost", port="3306", user="root", password="", database="food_ordering_db")
# cursor = mydb.cursor()
# selectquery = "select * from User"
# cursor.execute(selectquery)
# records = cursor.fetchall()
# print("No. of users:", cursor.rowcount)

# for row in records:
# 	print("User ID: ", row[0])
# 	print("User Name: ", row[1])
# 	print("Email: ", row[2])
# cursor.close()
# mydb.close()

# Accessing data using pandas method
try:
	mydb = connection.connect(host="localhost", port="3306", user="root", password="", database="food_ordering_db")
	query = "Select * from User;"
	result_dataFrame = pd.read_sql(query,mydb)
	print(result_dataFrame)
	mydb.close() #close the connection
except Exception as e:
	mydb.close()
	print(str(e))