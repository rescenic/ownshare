import subprocess
import os
import shutil

def build():
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


if __name__ == "__main__":
    build()
