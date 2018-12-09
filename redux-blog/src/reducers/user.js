export default (state={}, action) => {
  switch(action.type) {
    case 'HOME_PAGE_LOADED':
      return {
        ...state,
        users: action.data.users,
      };
    case 'USER_REGISTER':
      return action.res.data;
    case 'USER LOGIN':
      return {
        ...state,
        users: state.users.filter((user) => user.user_id !== action.user_id),
      };
    case 'USER_REGISTER_ERROR':
      return {
        ...state,
        users: ([action.data.user]).concat(state.user),
      };
    default:
      return state;
  }
};