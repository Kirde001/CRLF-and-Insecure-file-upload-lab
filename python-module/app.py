from flask import Flask, request
import requests
from lxml import etree
import jwt

app = Flask(__name__)

@app.route('/api/proxy')
def ssrf():
    url = request.args.get('url')
    if not url:
        return "Please provide a 'url' parameter", 400
    try:
        resp = requests.get(url, timeout=3)
        return resp.text
    except Exception as e:
        return str(e), 500

xml_db = b"""
<users>
    <user><username>admin</username><password>supersecret</password><role>admin</role></user>
    <user><username>guest</username><password>guest</password><role>user</role></user>
</users>
"""
root = etree.fromstring(xml_db)

@app.route('/api/user_info')
def xpath_inject():
    username = request.args.get('username')
    if not username:
        return "Provide a 'username' parameter", 400

    query = f"//user[username/text()='{username}']"
    try:
        result = root.xpath(query)
        if result:
            return f"User found: {result[0].find('username').text}, Role: {result[0].find('role').text}"
        return "User not found", 404
    except Exception as e:
        return f"XPath Error: {str(e)}", 500

@app.route('/api/admin_panel')
def jwt_auth():
    token = request.headers.get('Authorization', '').replace('Bearer ', '')
    if not token:
        return "Missing Authorization Bearer token", 401
    try:
        decoded = jwt.decode(token, options={"verify_signature": False})
        if decoded.get("role") == "admin":
            return "Welcome to Admin Panel! You successfully bypassed JWT."
        return "Access Denied: You are not admin", 403
    except Exception as e:
        return f"Token Error: {str(e)}", 400

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)