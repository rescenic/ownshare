<script>
    import Modal from "./Modal.svelte";

    import { createUser } from "$lib/api.js";
    import { createEventDispatcher } from 'svelte';

	const dispatch = createEventDispatcher();

    let error = "";

    let username = "";
    let email = "";
    let password = "";
    let passwordRepeat = "";
    let role = "manager";

    async function handleLogin() {

        if(username == "" || email == "" || password == "" || passwordRepeat == "") {
            error = "All fields must be filled!";
            return;
        }

        if(password != passwordRepeat) {
            error = "Password Repeat is wrong!";
            return;
        }

        let response = await createUser(username, email, password, role);

        if(response.error) {
            error = response.error;
        } else {
            dispatch("created", {});
        }

    }

    export let showModal = true;
</script>

<Modal bind:showModal={showModal}>
    <h2 class="text-2xl font-bold">Create User</h2>

    <span class="text-red-400">{error}</span>

    <div class="flex flex-col gap-3 w-72 mt-4">
        <input type="text" placeholder="Username" class="input input-bordered w-full" bind:value={username}/>
        <input type="email" placeholder="E-Mail" class="input input-bordered w-full" bind:value={email}/>
        <input type="password" placeholder="Password" class="input input-bordered w-full" bind:value={password}/>
        <input type="password" placeholder="Repeat Password" class="input input-bordered w-full" bind:value={passwordRepeat}/>
        <select class="select select-bordered w-full max-w-xs" bind:value={role}>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
        </select>
        <button class="btn btn-primary" on:click={handleLogin}>Create User</button>
    </div>
</Modal>