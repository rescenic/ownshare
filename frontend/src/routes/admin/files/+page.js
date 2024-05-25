import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
import { fetchFileCollections, fetchFileCollection } from "$lib/api.js";

export async function load({ params, fetch }) {

    let fileCollections = await fetchFileCollections(fetch);

	return {
		fileCollections
	};
}