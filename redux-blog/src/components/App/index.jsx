import React from 'react';
import { withRouter, Switch, Route } from 'react-router-dom';

import { Home, Register } from '../../components';

const App = (props) => {
	console.log(props);
  return (
    <Switch>
      <Route exact path="/" component={Home} />
      <Route exact path="/register" component={Register} />
    </Switch>
  )
}

export default withRouter(App);