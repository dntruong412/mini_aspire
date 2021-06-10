import User from './UserRepository';

const repositories = {
    User
};

export default {
    get: (name) => repositories[name]
};
