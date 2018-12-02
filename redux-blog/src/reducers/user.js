export default (state={users: []}, action) => {
  switch(action.type) {
    case 'HOME_PAGE_LOADED':
      return {
        ...state,
        users: action.data.users,
      };
    case 'USER_REGISTER':
      return {
        ...state,
        users: ([action.data.user]).concat(state.user),
      };
    case 'USER LOGIN':
      return {
        ...state,
        users: state.users.filter((user) => user.user_id !== action.user_id),
      };
    default:
      return state;
  }
};