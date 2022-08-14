import mysql.connector as connection
import pandas as pd

# Accessing data using pandas method
try:
	mydb = connection.connect(host="localhost", port="3306", user="root", password="", database="food_ordering_db")
	query = """Select o.orderID, o.foodID, c.userID 
							FROM `order` o, `cart` c
							WHERE o.cartID = c.cartID;"""
	result_dataFrame = pd.read_sql(query,mydb)
	print(result_dataFrame.head())
	mydb.close() #close the connection
except Exception as e:
	mydb.close()
	print(str(e))