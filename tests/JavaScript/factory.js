export const languageFactory = props => {
  return {
    name: 'Test Language',
    algo_code: 'TL',
    group: {},
    ...props
  };
};

export const groupFactory = props => {
  return {
    name: 'Test Group',
    ...props
  };
};
