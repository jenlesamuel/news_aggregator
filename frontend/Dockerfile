# Stage 1: Build
FROM node:16-alpine as build

WORKDIR /app

# Copy package.json and package-lock.json
COPY package.json package-lock.json ./

# Install dependencies using npm
RUN npm install

# Copy the rest of the project files
COPY . .

# Build the React project
RUN npm run build

# Stage 2: Serve
FROM nginx:alpine

# Copy the build folder from the build stage
COPY --from=build /app/build /usr/share/nginx/html

# Expose port 80 to access the frontend
EXPOSE 80

# Start Nginx to serve the React frontend
CMD ["nginx", "-g", "daemon off;"]
