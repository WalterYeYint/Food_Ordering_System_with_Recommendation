# %%
import mysql.connector as connection
import pandas as pd
import numpy as np
import pickle

# %%
# Establish connection with MySQL DB
mydb = connection.connect(host="localhost", port="3306", user="root", password="", database="food_ordering_db")

# %%
# Accessing food table
query = """Select *
						FROM `food`;"""
food = pd.read_sql(query,mydb)
print(food.head())

# Accessing user table
query = """Select *
						FROM `user`;"""
users = pd.read_sql(query,mydb)
print(users.head())

# %%
# Accessing userID and foodID data with ratings
query = """Select o.foodorderID, o.foodID, o.rating, c.userID 
						FROM `foodorder` o, `cart` c
						WHERE o.cartID = c.cartID;"""
orders = pd.read_sql(query,mydb)
print(orders.head())
print(list(orders.columns))
print("No. of orders - ", len(orders))

# %%
# Merge order and food queries
combine_food_order = pd.merge(orders, food, on="foodID")
print(combine_food_order.head())
print("No. of combine_food_order - ", len(combine_food_order))

# %%
print(f'Amount of unique foodIDs - {len(pd.unique(combine_food_order["foodID"]))}')
print(f'Amount of unique userIDs - {len(pd.unique(combine_food_order["userID"]))}')

# %%
with open("Recommendation_System/Recommendation_Model/combined_food_order" + ".csv", "w") as f:
	f.write(combine_food_order.to_csv())

# %% [markdown]
# Getting the number of times food was ordered

# %%
# combine_food_order = combine_food_order.dropna(axis = 0, subset = ['bookTitle'])
food_order_count = (combine_food_order.
     groupby(by = ["foodID"]).
     count().
     reset_index().
     sort_values(by = ["foodorderID"]).
     rename(columns = {"foodorderID": "totalCount"})
     [["foodID", "totalCount"]]
     )
food_order_count.tail()

# %%
pd.set_option('display.float_format', lambda x: '%.3f' % x)
print(food_order_count["totalCount"].describe())

# %%
order_with_total_count = combine_food_order.merge(food_order_count, left_on = 'foodID', right_on = 'foodID', how = 'left')
order_with_total_count["has_ordered"] = 5
print(order_with_total_count.loc[order_with_total_count["foodName"] == "Noodle Salad"])
# order_with_total_count.head()
print("No. of order_with_total_count - ", len(order_with_total_count))

# %%
with open("Recommendation_System/Recommendation_Model/order_with_total_count" + ".csv", "w") as f:
	f.write(order_with_total_count.to_csv())

# %%
from scipy.sparse import csr_matrix
order_with_total_count = order_with_total_count.drop_duplicates(['userID', 'foodName'])
# order_with_total_count_pivot = order_with_total_count.pivot(index = 'foodName', columns = 'userID', values = 'has_ordered').fillna(0)
order_with_total_count_pivot = order_with_total_count.pivot(index = 'foodName', columns = 'userID', values = 'has_ordered').fillna(0)
order_with_total_count_foodID_pivot = order_with_total_count.pivot(index = 'foodID', columns = 'userID', values = 'has_ordered').fillna(0)
order_with_total_count_matrix = csr_matrix(order_with_total_count_foodID_pivot.values)

from sklearn.neighbors import NearestNeighbors


model_knn = NearestNeighbors(metric = 'cosine', algorithm = 'brute')
model_knn.fit(order_with_total_count_matrix)

# %%
# Its important to use binary mode 
knnPickle = open('Recommendation_System/Recommendation_Model/knnpickle_file', 'wb') 
# source, destination 
pickle.dump(model_knn, knnPickle)  
# close the file
knnPickle.close()

# %%
with open("Recommendation_System/Recommendation_Model/order_with_total_count_pivot" + ".csv", "w") as f:
	f.write(order_with_total_count_pivot.to_csv())
with open("Recommendation_System/Recommendation_Model/order_with_total_count_pivot_id" + ".csv", "w") as f:
	f.write(order_with_total_count_foodID_pivot.to_csv())
with open("Recommendation_System/Recommendation_Model/query_index_to_foodID" + ".csv", "w") as f:
	f.write("query_index,foodID\n")
	pivot_foodID_list = list(order_with_total_count_foodID_pivot.index)
	for i in range(len(pivot_foodID_list)):
		f.write(f"{i},{pivot_foodID_list[i]}\n")

# %%
# query_index = np.random.choice(order_with_total_count_pivot.shape[0])
# print(query_index)
# query_index = 124
query_index = 32		#(points to foodID 4924 Real Milk)
distances, indices = model_knn.kneighbors(order_with_total_count_foodID_pivot.iloc[query_index,:].values.reshape(1, -1), n_neighbors = 6)

order_with_total_count_foodID_pivot.iloc[query_index,:].values.reshape(1,-1)

# %%
foodID = order_with_total_count_foodID_pivot.index[query_index]
foodName = food.foodName[foodID-1]
print(foodID)
print(foodName)

# %%
for i in range(0, len(distances.flatten())):
    if i == 0:
        print('Recommendations for {0}:\n'.format(foodName))
    else:
        foodID = order_with_total_count_foodID_pivot.index[indices.flatten()[i]]
        foodName = food.foodName[foodID-1]
        print('{0}: {1} {2}, with distance of {3}:'.format(i, foodID, foodName, distances.flatten()[i]))


