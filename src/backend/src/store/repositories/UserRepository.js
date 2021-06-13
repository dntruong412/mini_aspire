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

  async createUser(info) {
    const config = {
      method: 'post',
      url: `${resource}`,
      headers: {
        'Content-Type': 'application/json',
      },
      data: info,
    };
    const { data } = await axios(config);

    return data;
  },

  async updateUser(user_id, info) {
    const config = {
      method: 'post',
      url: `${resource}/${user_id}`,
      headers: {
        'Content-Type': 'application/json',
      },
      data: info,
    };
    const { data } = await axios(config);

    return data;
  },
};
