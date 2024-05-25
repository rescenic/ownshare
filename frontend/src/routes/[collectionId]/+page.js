import { fetchFileCollection } from "$lib/api.js"

export async function load({ params, fetch }) {

    let collection = await fetchFileCollection(params.collectionId, fetch );

    return {
        collectionId: params.collectionId,
        collection: collection,
    }
}