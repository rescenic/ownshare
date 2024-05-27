<script>
    import Modal from "./Modal.svelte";

    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
    import { getOption, setOption } from "$lib/api.js";
    import { invalidateAll } from '$app/navigation';

    let error = "";



    function truncate(str, n){
        var split = str.split('.');
        var filename = split[0];
        var extension = split[1];
        if (filename.length > n) {
            filename = filename.substring(0, n);
        }
        var result = filename + '...' + extension;
        return result;
    };

    export let showModal = true;
</script>

<Modal bind:showModal={showModal}>
    <h2 class="text-2xl font-bold mb-4">Create User</h2>

    <span class="text-red-300">{error}</span>

    <div class="flex flex-col gap-3 w-72">
        <input type="text" placeholder="Username" class="input input-bordered w-full" />
        <input type="email" placeholder="E-Mail" class="input input-bordered w-full" />
        <input type="password" placeholder="Password" class="input input-bordered w-full" />
        <input type="password" placeholder="Repeat Password" class="input input-bordered w-full" />
        <select class="select select-bordered w-full max-w-xs">
            <option disabled selected>Role</option>
            <option>Admin</option>
            <option>Manager</option>
        </select>
        <button class="btn btn-primary">Create User</button>
    </div>
</Modal>