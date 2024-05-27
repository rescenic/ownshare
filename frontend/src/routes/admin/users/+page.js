import { fetchUsers } from "$lib/api.js";

export async function load({ params, fetch }) {

    let users = await fetchUsers(fetch);

	return {
		users
	};
}