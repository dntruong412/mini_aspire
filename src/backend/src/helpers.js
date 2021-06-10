import axios from 'axios';

// Axios
export const defaultAxios = axios.create({
    baseURL: `${process.env.API_URL}/backend`
});


export function calculateMonthlyMinRepayment({ amount, interest_rate, duration }) {
    const repaymentInterest = amount * ((interest_rate / 100) / duration);
    return repaymentInterest + (amount / duration);
}

export function currencyFormat(value) {
    return new Intl.NumberFormat().format(value);
}