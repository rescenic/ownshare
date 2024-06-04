<script>
    import { fetchFileCollection, getConfig } from "$lib/api.js";
    import { page } from '$app/stores'
	import { onMount } from "svelte";
    import Handlebars from "handlebars";
    import { base } from '$app/paths'

    $: collection = {};

    $: html = "";
    

    onMount(async () => {

        const cfg = await getConfig();
        let backendAddress = cfg.backendAddress;

        let response = await fetch(base + "/theme/template.html");
        let templateHTML = await response.text();
        let template = Handlebars.compile(templateHTML);

        let collectionId = $page.url.searchParams.get("q");
        collection = await fetchFileCollection(collectionId, backendAddress);

        console.log(collection)

        if(!collection.error) {
            for(let i = 0; i < collection.files.length; i++) {
                collection.files[i].index = i + 1;
                collection.files[i].formatedSize = formatBytes(collection.files[i].size); 
                collection.files[i].url = backendAddress + collection.files[i].location;
            }

            collection.zipUrl = backendAddress + collection.path + "/" + collection.collection_id + ".zip";
        }

        html = template({ collection });
    });

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

</script>

<link rel="stylesheet" href="{base}/theme/style.css">
{@html html }

<!-- <div class="w-full h-screen flex items-center justify-center bg-base-300">
    <div class="card bg-base-100 p-8">
        <h1 class="text-center text-2xl font-bold mb-4">Download Files</h1>
        
        <div class="flex flex-col gap-4">
            {#if collection.files != null}
                {#each collection.files as file}
                    <a class="btn btn-primary" download href="{PUBLIC_BACKEND_ADDRESS + file.location}">{file.name}</a>
                {/each}

                <a class="btn btn-primary" download href="{PUBLIC_BACKEND_ADDRESS + collection.path + "/" + collection.collection_id + ".zip"}">Download All</a>
            {/if}

        </div>
    </div>
</div> -->