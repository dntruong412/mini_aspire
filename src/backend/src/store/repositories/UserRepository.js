import { defaultAxios as axios } from '@/helpers';

const resource = 'users';

export default {
    async getUsers(query = {}) {
        const config = {
            method: 'get',
            url: `${resource}`,
            headers: {
                'Content-Type': 'application/json',
            },
            params: query,
        };
        const { data } = await axios(config);

        return data;
    },

    async getUser(user_id) {
        const config = {
            method: 'get',
            url: `${resource}/${user_id}`,
            headers: {
                'Content-Type': 'application/json',
            },
        };
        const { data } = await axios(config);

        return data;
    },

    async getUserLoans(user_id, query = {}) {
        const config = {
            method: 'get',
            url: `${resource}/${user_id}/loan`,
            headers: {
                'Content-Type': 'application/json',
            },
            params: query,
        };
        const { data } = await axios(config);

        return data;
    },

    async getUserLoan(user_id, user_loan_id) {
        const config = {
            method: 'get',
            url: `${resource}/${user_id}/loan/${user_loan_id}`,
            headers: {
                'Content-Type': 'application/json',
            },
        };
        const { data } = await axios(config);

        return data;
    },

    async getUserLoanRepayments(user_id, user_loan_id) {
        const config = {
            method: 'get',
            url: `${resource}/${user_id}/loan/${user_loan_id}/repayments`,
            headers: {
                'Content-Type': 'application/json',
            },
        };
        const { data } = await axios(config);

        return data;
    },

    async submitLoan(user_id, postData) {
        const config = {
            method: 'post',
            url: `${resource}/${user_id}/loan`,
            headers: {
                'Content-Type': 'application/json',
            },
            data: postData,
        };
        const { data } = await axios(config);

        return data;
    },

    async submitRepayment(user_id, postData) {
        const config = {
            method: 'post',
            url: `${resource}/${user_id}/pay`,
            headers: {
                'Content-Type': 'application/json',
            },
            data: postData,
        };
        const { data } = await axios(config);

        return data;
    },
};
