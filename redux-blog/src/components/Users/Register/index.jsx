import axios from 'axios';
import React from 'react';
import { connect } from 'react-redux';

class Register extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      errorMessage: '',
    }

    this.handleChangeField = this.handleChangeField.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentWillReceiveProps(nextProps) {
  	
  }

  handleSubmit(){
    const { onSubmit, userToEdit, onEdit} = this.props;
    const { first_name, last_name, email, password, errorMessage } = this.state;

    if(!userToEdit) {
    		console.log('Post');
      return axios.post('http://api.he2examples.local/users', {
        first_name,
        last_name,
        email,
        password,
      })
        .then((res) => onSubmit(res.data))
        //.then(() => this.setState({ first_name: '', last_name: '', email: '', password: '' }))
        .catch(error => {
        		
        		if (error.response) {
        			this.setState({ errorMessage : error.response.data})
        		}
        		
      });
        
    } else {
    		console.log('Patchas');
      return axios.patch(`http://api.he2examples.local/users${userToEdit._id}`, {
        first_name,
        last_name,
        email,
        password,
      })
        .then((res) => onEdit(res.data))
        .then(() => this.setState({ first_name: '', last_name: '', email: '', password: '' }));
    }
  }

  handleChangeField(key, event) {
    this.setState({
      [key]: event.target.value,
    });
  }

  render() {
    const { userToEdit } = this.props;
    const { first_name, last_name, email, password, errorMessage } = this.state;

    return (
      <div className="col-12 col-lg-6 offset-lg-3">
      	<h1>Register To The Site</h1>
      	
        <input
          onChange={(ev) => this.handleChangeField('first_name', ev)}
          value={first_name}
          className="form-control my-3"
          placeholder="Enter Your First Name"
        />
        <input
          onChange={(ev) => this.handleChangeField('last_name', ev)}
          value={last_name}
          className="form-control my-3"
          placeholder="Enter Your Last Name"
        />
        <input
          onChange={(ev) => this.handleChangeField('email', ev)}
          value={email}
          className="form-control my-3"
          placeholder="Enter Your Email"
        />
        <input
        	  type="password"
          onChange={(ev) => this.handleChangeField('password', ev)}
          value={password}
          className="form-control my-3"
          placeholder="Enter Your Password"
        />
        <div className="alert alert-danger">{errorMessage}</div>
        <button onClick={this.handleSubmit} className="btn btn-primary float-right">{userToEdit ? 'Update' : 'Submit'}</button>
      </div>
    )
  }
}

const mapDispatchToProps = dispatch => ({
  onSubmit: data => dispatch({ type: 'USER_REGISTER', data }),
  onEdit: data => dispatch({ type: 'USER_UPDATE', data }),
});

const mapStateToProps = state => ({
  userToEdit: state.home.userToEdit,
});

export default connect(mapStateToProps, mapDispatchToProps)(Register);