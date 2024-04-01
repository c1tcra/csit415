from flask import Flask, render_template, flash, redirect, url_for, request
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager, login_user, logout_user, current_user, login_required
from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, SubmitField
from wtforms.validators import DataRequired
from werkzeug.security import generate_password_hash, check_password_hash
from functools import wraps
from config import Config
from models import User, Customer, Car, Service, Lead
from forms import CustomerForm, ServiceForm, ServiceBookingForm

app = Flask(__name__)
app.config.from_object(Config)
db = SQLAlchemy(app)

login_manager = LoginManager(app)
login_manager.login_view = 'login'

# LoginForm and other forms should be defined in a separate forms.py or within app.py
class LoginForm(FlaskForm):
    username = StringField('Username', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])
    submit = SubmitField('Sign In')

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

def requires_roles(*roles):
    def wrapper(fn):
        @wraps(fn)
        def decorated_view(*args, **kwargs):
            if not current_user.is_authenticated or current_user.role not in roles:
                flash("You do not have permission to access this resource.")
                return redirect(url_for('login'))
            return fn(*args, **kwargs)
        return decorated_view
    return wrapper

@app.route('/admin/users')
@login_required
@requires_roles('Admin')
def admin_users():
    users = User.query.all()
    return render_template('admin_users.html', users=users)



@app.route('/')
def index():
    return "Welcome to the Dealership CRM!"

@app.route('/login', methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('index'))
    form = LoginForm()
    if form.validate_on_submit():
        user = User.query.filter_by(username=form.username.data).first()
        if user and user.check_password(form.password.data):
            login_user(user)
            return redirect(url_for('index'))
        else:
            flash('Invalid username or password')
    return render_template('login.html', form=form)

@app.route('/logout')
def logout():
    logout_user()
    return redirect(url_for('index'))

# Example of an admin-only view
@app.route('/admin/dashboard')
@login_required
@requires_roles('Admin')
def admin_dashboard():
    return "Admin Dashboard"

# Example of a sales representative view
@app.route('/sales/customers')
@login_required
@requires_roles('Sales')
def sales_customers():
    # Assuming Customer model and current_user's sales relationship is defined
    return "Sales Representative's Customers"

# Example of a service technician view
@app.route('/service/records')
@login_required
@requires_roles('Technician')
def service_records():
    # Assuming Service and Car models are defined with relationships
    return "Service Records for Technician"

@app.route('/sales/add_customer', methods=['GET', 'POST'])
@login_required
@requires_roles('Sales')
def add_customer():
    form = CustomerForm()  # Assume you have a WTForms form for customer details
    if form.validate_on_submit():
        new_customer = Customer(name=form.name.data, email=form.email.data, sales_rep_id=current_user.id)
        db.session.add(new_customer)
        db.session.commit()
        flash('Customer added successfully!')
        return redirect(url_for('view_customers'))
    return render_template('add_customer.html', form=form)

@app.route('/sales/customers')
@login_required
@requires_roles('Sales')
def view_customers():
    customers = Customer.query.filter_by(sales_rep_id=current_user.id).all()
    return render_template('view_customers.html', customers=customers)

@app.route('/service/log_service', methods=['GET', 'POST'])
@login_required
@requires_roles('Technician')
def log_service():
    form = ServiceForm()  # Assuming a form for service details exists
    if form.validate_on_submit():
        new_service = Service(car_id=form.car_id.data, service_type=form.service_type.data, service_date=form.service_date.data)
        db.session.add(new_service)
        db.session.commit()
        flash('Service logged successfully!')
        return redirect(url_for('view_services'))
    return render_template('log_service.html', form=form)

@app.route('/service/services')
@login_required
@requires_roles('Technician')
def view_services():
    services = Service.query.all()  # Adjust as needed to filter for the technician's services
    return render_template('view_services.html', services=services)

@app.route('/customer/my_vehicle')
@login_required
@requires_roles('Customer')
def my_vehicle():
    vehicle = Car.query.filter_by(owner_id=current_user.id).first()  # Assuming a relation exists
    services = Service.query.filter_by(car_id=vehicle.id).all() if vehicle else []
    return render_template('my_vehicle.html', vehicle=vehicle, services=services)

@app.route('/sales/dashboard')
@login_required
@requires_roles('Sales')
def sales_dashboard():
    # Placeholder data - replace with actual data queries
    sales_data = {
        'total_sales': 150,
        'sales_by_month': [10, 20, 15, 30, 25, 50],
        'top_selling_models': [('Model X', 40), ('Model Y', 30), ('Model Z', 20)]
    }
    return render_template('sales_dashboard.html', sales_data=sales_data)

@app.route('/sales/leads', methods=['GET', 'POST'])
@login_required
@requires_roles('Sales')
def sales_leads():
    if request.method == 'POST':
        # Process your form data and add/update lead
        pass
    leads = Lead.query.filter_by(assigned_rep_id=current_user.id).all()
    return render_template('sales_leads.html', leads=leads)

@app.route('/customer/service_records')
@login_required
@requires_roles('Customer')
def customer_service_records():
    services = Service.query.filter_by(customer_id=current_user.id).all()
    return render_template('customer_service_records.html', services=services)

@app.route('/customer/book_service', methods=['GET', 'POST'])
@login_required
@requires_roles('Customer')
def book_service():
    form = ServiceBookingForm()  # Assume a corresponding WTForm
    if form.validate_on_submit():
        # Process booking request
        flash('Service booked successfully!')
        return redirect(url_for('customer_service_records'))
    return render_template('book_service.html', form=form)


# Add more routes and functionalities as needed

if __name__ == '__main__':
    app.run(debug=True)