<script>
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
	export let data;
</script>

<div class="w-full h-screen flex items-center justify-center bg-base-300">
    <div class="card bg-base-100 p-8">
        <h1 class="text-center text-2xl font-bold mb-4">Download Files</h1>
    
        <div class="flex flex-col gap-4">
            {#each data.collection.files as file}
                <a class="btn btn-primary" download href="{PUBLIC_BACKEND_ADDRESS + file.location}">{file.name}</a>
            {/each}

            <a class="btn btn-primary" download href="{PUBLIC_BACKEND_ADDRESS + data.collection.path + "/" + data.collection.collection_id + ".zip"}">Download All</a>
        </div>
    </div>
</div>