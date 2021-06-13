import {
    GET_USERS,
    GET_USER,
    USER_SUBMIT_LOAN,
    USER_SUBMIT_REPAYMENT,
    USER_GET_LOAN,
    USER_GET_LOAN_REPAYMENTS,
    USER_GET_LOANS,
} from '@/store/mutation-types';
import RepositoryFactory from '@/store/repositories/RepositoryFactory';

export default {
    state: {
        users: [],
        loans: [],
        user: null,
        loan: null,
        loan_repayments: [],
    },
    mutations: {
        [GET_USERS](state, users) {
            state.users = users;
        },
        [GET_USER](state, user) {
            state.user = user;
        },
        [USER_GET_LOANS](state, loans) {
            state.loans = loans;
        },
        [USER_GET_LOAN](state, loan) {
            state.loan = loan;
        },
        [USER_GET_LOAN_REPAYMENTS](state, loan_repayments) {
            state.loan_repayments = loan_repayments;
        },
    },
    actions: {
        async [GET_USERS](context, query = {}) {
            const UserRepository = RepositoryFactory.get('User');
            let users = [];
            if (Object.keys(query).length > 0) {
                users = await UserRepository.getUsers(query);
            } else {
                users = await UserRepository.getUsers();
            }
            context.commit(GET_USERS, users?.data || []);

            return users;
        },
        async [GET_USER](context, user_id) {
            try {
                const result = await RepositoryFactory.get('User').getUser(user_id);
                if (result.status == 1) {
                    context.commit(GET_USER, result.data);
                }
                return result;
            } catch (error) {
                return error.response ? error.response.data : error;
            }
        },
        async [USER_GET_LOANS](context, { user_id, query = {} }) {
            const UserRepository = RepositoryFactory.get('User');
            let loans = null;
            if (Object.keys(query).length > 0) {
                loans = await UserRepository.getUserLoans(user_id, query);
            } else {
                loans = await UserRepository.getUserLoans(user_id);
            }
            context.commit(USER_GET_LOANS, loans?.data || []);
            return loans;
        },
        async [USER_GET_LOAN](context, { user_id, user_loan_id }) {
            const UserRepository = RepositoryFactory.get('User');
            const loan = await UserRepository.getUserLoan(user_id, user_loan_id);
            if (loan.status == 1) {
                context.commit(USER_GET_LOAN, loan.data);
            }
            return loan;
        },
        async [USER_GET_LOAN_REPAYMENTS](context, { user_id, user_loan_id }) {
            const UserRepository = RepositoryFactory.get('User');
            const repayments = await UserRepository.getUserLoanRepayments(user_id, user_loan_id);
            if (repayments.status == 1) {
                context.commit(USER_GET_LOAN_REPAYMENTS, repayments.data);
            }
            return repayments;
        },
        async [USER_SUBMIT_LOAN](context, { user_id, data }) {
            try {
                return await RepositoryFactory.get('User').submitLoan(user_id, data);
            } catch (error) {
                return error.response ? error.response.data : error;
            }
        },
        async [USER_SUBMIT_REPAYMENT](context, { user_id, data }) {
            try {
                return await RepositoryFactory.get('User').submitRepayment(user_id, data);
            } catch (error) {
                return error.response ? error.response.data : error;
            }
        },
    },
};
