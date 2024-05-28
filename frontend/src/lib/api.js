import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"

export async function getOption(option) {
    let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/options/getOption.php/?option=" + option, {
        method: "GET",
        credentials: "include",
    });

    let result = await response.json();

    if(result.error != null) {
        console.log("error while fetching option: " + option);
        console.log(result.error)
        return "";
    }
    return result.value;
}

export async function setOption(name, value) {
    let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/options/setOption.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            optionName: name,
            optionValue: value
        })
    });

    let result = await response.json();

    if(result.error != null) {
        console.log("error while setting option: " + name);
        pageError += result.error + ";";
        return "";
    }
}

export async function fetchFileCollection(id, f) {
    let url = PUBLIC_BACKEND_ADDRESS + "/getCollection.php?collectionId=" + id;
    let body = {
        method: "GET",
        credentials: "include",
    }

    let result = null;

    if(f) {
        result = await f(url, body);
    } else {
        result = await fetch(url, body);
    }


    let collections = await result.json();
    return collections;
}

export async function fetchFileCollections(f) {
    let body = {
        method: "GET",
        credentials: "include"
    }

    let result = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/files/getCollections.php", body);


    let collections = await result.json();
    return collections;
}

export async function deleteFileCollections(id) {
    let fd = new FormData();
    fd.set("collection_id", id);

    let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/files/deleteCollection.php", {
        method: "POST",
        credentials: "include",
        body: fd
    });

    let result = await response.json();
    return result;
}

export async function fetchUsers() {
    let result = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/users/getUsers.php", {
        method: "GET",
        credentials: "include"
    });

    let users = await result.json();
    return users;
}

export async function createUser(username, email, password, role) {
    let fd = new FormData();
    fd.set("username", username);
    fd.set("email", email);
    fd.set("password", password);
    fd.set("role", role);

    let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/users/createUser.php", {
        method: "POST",
        credentials: "include",
        body: fd
    });

    let result = await response.text();
    console.log(result);
    return JSON.parse(result);
} 

export async function deleteUser(id) {
    let fd = new FormData();
    fd.set("user_id", id);

    let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/admin/users/deleteUser.php", {
        method: "POST",
        credentials: "include",
        body: fd
    });

    let result = await response.text();
    console.log(result);
    return JSON.parse(result);
} 