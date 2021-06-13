import User from './UserRepository';
import UserLoan from './UserLoanRepository';

const repositories = {
    User,
    UserLoan
};

export default {
    get: (name) => repositories[name]
};
