from flask import Flask, jsonify
import pandas as pd
import pickle
import time
import threading
from multiprocessing import Process
import datetime
import mysql.connector as connection
import numpy as np
import os

def read_data_and_model():
	data = pd.read_csv("Recommendation_System/Recommendation_Model/query_index_to_foodID.csv")

	# order_with_total_count = pd.read_csv("Recommendation_System/Recommendation_Model/order_with_total_count.csv")
	# order_with_total_count = order_with_total_count.drop_duplicates(['userID', 'foodName'])
	# order_with_total_count_foodID_pivot = order_with_total_count.pivot(index = 'foodID', columns = 'userID', values = 'has_ordered').fillna(0)
	order_with_total_count_foodID_pivot = pd.read_csv("Recommendation_System/Recommendation_Model/order_with_total_count_pivot_id.csv", index_col='foodID')

	# load the model from disk
	model_knn = pickle.load(open('Recommendation_System/Recommendation_Model/knnpickle_file', 'rb'))
	return data, order_with_total_count_foodID_pivot, model_knn

app = Flask(__name__)

def thread_two_func(n, name):
	training_hour = 10
	training_minute = 35
	global server
	global restarted
	global model_is_retrained
	while True:
		current_time = datetime.datetime.now()
		current_hour = current_time.hour
		current_minute = current_time.minute
		# print("printing ", current_time)
		time.sleep(1)
		if training_hour == current_hour and training_minute == current_minute:
			if restarted == True and model_is_retrained == False:
				server.terminate()
				print("Server terminated !!!")
				os.system("python Recommendation_System/Recommendation_Model/knn_model.py")
				time.sleep(5)
				restarted = False
				model_is_retrained = True
		else:
			model_is_retrained = False

@app.route('/')
def index():
	return "Welcome to the API App"

@app.route("/get_recommendation/<foodID>", methods=['GET'])
def get(foodID):
	recommended_foodID_list = {}
	row = data.loc[data.foodID == int(foodID)]
	query_index = list(row.query_index)[0]
	distances, indices = model_knn.kneighbors(order_with_total_count_foodID_pivot.iloc[query_index,:].values.reshape(1, -1), n_neighbors = 6)
	for i in range(0, len(distances.flatten())):
		recommended_foodID = order_with_total_count_foodID_pivot.index[indices.flatten()[i]]
		recommended_foodID_list[f"{i}"] = str(recommended_foodID)
	return recommended_foodID_list
	# return "Hello There!!!"

if __name__ == "__main__":
	thread_two = threading.Thread(target=thread_two_func, args=(1,'thread_two'))
	thread_two.start()
	restarted = False
	model_is_retrained = False
	while True:
		if restarted == False:
			data, order_with_total_count_foodID_pivot, model_knn = read_data_and_model()
			server = Process(target=app.run)		# debug=False by default
			server.start()
			restarted = True
			model_is_retrained = True
		time.sleep(10)

	# app.run(debug=False)