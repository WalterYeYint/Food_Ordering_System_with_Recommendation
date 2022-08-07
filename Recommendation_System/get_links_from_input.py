from bs4 import BeautifulSoup
import requests
import pandas as pd
import csv
import json

data = pd.read_csv("bkup_restaurants.csv", delimiter=",", encoding="utf-8", index_col=False)
with open("restaurant_links.csv", "w") as f:
	for x in data.link:
		f.write(str(x)+'\n')