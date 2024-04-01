from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField, IntegerField, SelectField, DateField
from wtforms.validators import DataRequired

class CustomerForm(FlaskForm):
    name = StringField('Name', validators=[DataRequired()])
    email = StringField('Email', validators=[DataRequired()])
    submit = SubmitField('Submit')

class ServiceForm(FlaskForm):
    service_type = StringField('Service Type', validators=[DataRequired()])
    service_date = DateField('Service Date', format='%Y-%m-%d', validators=[DataRequired()])
    car_id = IntegerField('Car ID', validators=[DataRequired()])  # Assume you have a way to select or input this
    submit = SubmitField('Submit')