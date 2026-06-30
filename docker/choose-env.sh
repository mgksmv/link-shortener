#!/usr/bin/env bash

get_compose_file() {
    case "$1" in
        "dev")
            echo "docker-compose.yml"
            ;;
        "prod")
            echo "docker-compose.prod.yml"
            ;;
        *)
            echo "docker-compose.yml"
            ;;
    esac
}

# Check if DOCKER_ENV is already set in .env and use it
if [ -f ".env" ] && grep -q "^DOCKER_ENV=" .env; then
    env_value=$(grep "^DOCKER_ENV=" .env | cut -d'=' -f2)
    docker_compose_file=$(get_compose_file "$env_value")
    echo "$docker_compose_file"
    exit 0
fi

if [ ! -f ".env" ]; then
    echo "❌  Error: .env file not found in the current directory!" >&2
    echo "Please create a .env file first." >&2
    exit 1
fi

read -p "🌿  Choose environment [dev/prod] (default is dev): " env >&2

# Set default to dev if empty input
if [ -z "$env" ]; then
    env="dev"
fi

case "$env" in
    "dev"|"prod")
        # Valid environment
        ;;
    *)
        echo "⚠️  Invalid environment '$env'. Using 'dev' as default." >&2
        env="dev"
        ;;
esac

docker_compose_file=$(get_compose_file "$env")

if grep -q "^DOCKER_ENV=" .env; then
    # Replace existing DOCKER_ENV line
    sed -i "s/^DOCKER_ENV=.*/DOCKER_ENV=$env/" .env
    echo "✅  Updated DOCKER_ENV=$env in .env file" >&2
else
    # Add DOCKER_ENV to the end of the file
    echo "DOCKER_ENV=$env" >> .env
    echo "✅  Added DOCKER_ENV=$env to .env file" >&2
fi

echo "🐳  Using: $docker_compose_file" >&2

echo "$docker_compose_file"
