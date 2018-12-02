import { createStore, combineReducers } from 'redux';

import { home, user } from './reducers';

const reducers = combineReducers({
  home, user
});

const store = createStore(reducers);

export default store;