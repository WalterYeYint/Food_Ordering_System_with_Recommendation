from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
import pandas as pd
import time

data = pd.read_csv("popular_restaurants.csv", delimiter=",", encoding="utf-8")		# Doesn't support myanmar font
print(data.columns)
print(len(data))
print(data.link[0])

options = Options()
options.add_argument('--headless')
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')
chromedriver_link = "/home/kan/.wdm/drivers/chromedriver/linux64/99.0.4844/chromedriver"

for i in range(len(data)):
	driver = webdriver.Chrome(chromedriver_link)
	with open("selenium_restaurant_html/" + data.name[i] + ".html", "w") as f:
		print("Processing ", i, " th restaurant ", data.name[i], "...")
		driver.get(data.link[i])
		elem = driver.page_source
		f.write(elem)
	time.sleep(300)
driver.close()

