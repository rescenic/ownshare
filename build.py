import subprocess
import os
import shutil
import json
from dotenv import load_dotenv

def build(db_host, db_name, db_user, db_password, root_url):
    print("starting build...")
    print("building sveltekit app...")

    # Execute `npm run build` in the subdir `./frontend`
    frontend_dir = "./frontend"
    result = subprocess.run(["npm", "run", "build"], cwd=frontend_dir, capture_output=True, text=True)
    if result.returncode != 0:
        print(f"Build failed with error: {result.stderr}")
        return
    print(result.stdout)

    # Create a new folder in the root dir of this file called `build`
    build_dir = "./build"

    if os.path.exists(build_dir):
        shutil.rmtree(build_dir)
        print(f"Deleted existing directory {build_dir}")

    os.makedirs(build_dir, exist_ok=True)
    print(f"Created directory {build_dir}")

    # Copy the folder contents of `./frontend/build` to `./build`
    frontend_build_dir = os.path.join(frontend_dir, "build")
    if os.path.exists(frontend_build_dir):
        for item in os.listdir(frontend_build_dir):
            s = os.path.join(frontend_build_dir, item)
            d = os.path.join(build_dir, item)
            if os.path.isdir(s):
                shutil.copytree(s, d, dirs_exist_ok=True)
            else:
                shutil.copy2(s, d)
        print(f"Copied contents from {frontend_build_dir} to {build_dir}")
    else:
        print(f"{frontend_build_dir} does not exist, build might have failed.")

    # Copy the folder `./api` inside `./build`
    api_dir = "./api"
    if os.path.exists(api_dir):
        shutil.copytree(api_dir, os.path.join(build_dir, "api"), dirs_exist_ok=True)
        print(f"Copied {api_dir} to {build_dir}")
    else:
        print(f"{api_dir} does not exist.")

    # Set the contents of ./build/config.json
    config_json_path = os.path.join(build_dir, "config.json")
    config_json_content = {
        "backendAddress": root_url + "api"
    }
    with open(config_json_path, "w") as config_json_file:
        json.dump(config_json_content, config_json_file, indent=4)
    print(f"Set {config_json_path} to {config_json_content}")

    # Set the contents of ./build/api/config.php
    config_php_path = os.path.join(build_dir, "api", "config.php")
    config_php_content = f"""<?php
define("APP_SETUP_COMPLETE", false);

define("APP_CORS_URLS", "{root_url}");
define("APP_DB_HOST", "{db_host}");
define("APP_DB_NAME", "{db_name}");
define("APP_DB_USER", "{db_user}");
define("APP_DB_PASSWORD", "{db_password}");
"""
    with open(config_php_path, "w") as config_php_file:
        config_php_file.write(config_php_content)
    print(f"Set {config_php_path} to provided database and URL configuration")


if __name__ == "__main__":
    load_dotenv()

    db_host = os.getenv("DB_HOST")
    db_name = os.getenv("DB_NAME")
    db_user = os.getenv("DB_USER")
    db_password = os.getenv("DB_PASSWORD")
    root_url = os.getenv("ROOT_URL")

    if not all([db_host, db_name, db_user, db_password, root_url]):
        print("Error: One or more environment variables are missing.")
        print("Required vars: DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, ROOT_URL")
        exit(1)

    build(db_host, db_name, db_user, db_password, root_url)
