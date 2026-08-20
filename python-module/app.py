from flask import Flask, request, render_template_string, make_response, redirect
import requests
from lxml import etree
import jwt
import datetime

app = Flask(__name__)
SECRET_KEY = "super_secret_key"

LOGIN_HTML = """
<!DOCTYPE html>
<html>
<head><title>Login - JWT Lab</title></head>
<body style="font-family: Arial; margin: 40px;">
    <h2>Authentication Lab</h2>
    <div style="border: 1px solid #ccc; padding: 20px; width: 300px;">
        <form method="POST" action="/login">
            <label>Username:</label><br>
            <input type="text" name="username" value="guest" style="width: 100%; margin-bottom: 10px;"><br>
            <label>Password:</label><br>
            <input type="password" name="password" value="guest" style="width: 100%; margin-bottom: 10px;"><br>
            <input type="submit" value="Login">
        </form>
    </div>
    <p><i>Подсказка: авторизуйтесь как guest/guest. Токен сохранится в Cookies.</i></p>
</body>
</html>
"""

DASHBOARD_HTML = """
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body style="font-family: Arial; margin: 40px;">
    <h2>Dashboard</h2>
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 5px;">
        <h3>Статус: {{ message }}</h3>
        <p>Ваша роль: <b>{{ role }}</b></p>
        {% if role == 'admin' %}
            <p style="color: red; font-weight: bold;">ФЛАГ: WAF_BYPASS_SUCCESS_1337</p>
        {% endif %}
    </div>
    <br><a href="/logout">Logout</a>
</body>
</html>
"""

@app.route('/')
def index():
    return render_template_string(LOGIN_HTML)

@app.route('/login', methods=['POST'])
def login():
    user = request.form.get('username')
    payload = {
        'user': user,
        'role': 'guest',
        'exp': datetime.datetime.utcnow() + datetime.timedelta(hours=1)
    }
    token = jwt.encode(payload, SECRET_KEY, algorithm='HS256')
    
    resp = make_response(redirect('/dashboard'))
    resp.set_cookie('auth_token', token)
    return resp

@app.route('/dashboard')
def dashboard():
    token = request.cookies.get('auth_token')
    if not token:
        return redirect('/')
    
    try:
        decoded = jwt.decode(token, options={"verify_signature": False})
        
        if decoded.get('role') == 'admin':
            return render_template_string(DASHBOARD_HTML, message="Доступ разрешен (Admin)", role="admin")
        else:
            return render_template_string(DASHBOARD_HTML, message="Доступ ограничен", role=decoded.get('role'))
    except Exception as e:
        return f"Token Error: {str(e)}"

@app.route('/logout')
def logout():
    resp = make_response(redirect('/'))
    resp.set_cookie('auth_token', '', expires=0)
    return resp

@app.route('/api/proxy')
def ssrf():
    url = request.args.get('url', '')
    try:
        return requests.get(url, timeout=3).text
    except Exception as e:
        return str(e)

@app.route('/api/user_info')
def xpath_inject():
    xml_db = b"<users><user><username>admin</username></user></users>"
    root = etree.fromstring(xml_db)
    username = request.args.get('username', '')
    try:
        result = root.xpath(f"//user[username/text()='{username}']")
        return "Found" if result else "Not found"
    except Exception as e:
        return f"XPath Error: {str(e)}"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)