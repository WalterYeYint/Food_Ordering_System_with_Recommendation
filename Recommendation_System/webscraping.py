from os import closerange
from bs4 import BeautifulSoup
from pkg_resources import resource_listdir
import requests

with open("popular_restaurants" + ".csv", "w") as f:
	headers = {'User-Agent': 'window.navigator.userAgent'}
	html_text = requests.get('https://www.foodpanda.com.mm/en/city/yangon', headers=headers).text
	soup = BeautifulSoup(html_text, 'lxml')
	restaurant_list_uls = soup.find('ul', class_ = 'vendor-list')
	print(str(len(restaurant_list_uls)) + " restaurants found")
	restaurant_names_spans = restaurant_list_uls.find_all('span', class_ = 'name fn')
	# print(restaurant_names_spans)
	for restaurant_name in restaurant_names_spans:
		f.write(str(restaurant_name.text) + '\n')
		