from flask import Flask, jsonify

app = Flask(__name__)

@app.route('/')
def index():
	return "Welcome to the API App"

@app.route("/get/<user_id>", methods=['GET'])
def get(user_id):
	return f"The user id is {user_id} and one added {int(user_id)+1}"

if __name__ == "__main__":
	app.run(debug=True)