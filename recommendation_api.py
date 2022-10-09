from flask import Flask, jsonify
import pandas as pd
import pickle

data = pd.read_csv("Recommendation_System/Recommendation_Model/query_index_to_foodID.csv")

# order_with_total_count = pd.read_csv("Recommendation_System/Recommendation_Model/order_with_total_count.csv")
# order_with_total_count = order_with_total_count.drop_duplicates(['userID', 'foodName'])
# order_with_total_count_foodID_pivot = order_with_total_count.pivot(index = 'foodID', columns = 'userID', values = 'has_ordered').fillna(0)
order_with_total_count_foodID_pivot = pd.read_csv("Recommendation_System/Recommendation_Model/order_with_total_count_pivot_id.csv", index_col='foodID')

# load the model from disk
model_knn = pickle.load(open('Recommendation_System/Recommendation_Model/knnpickle_file', 'rb'))

app = Flask(__name__)

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
	app.run(debug=True)