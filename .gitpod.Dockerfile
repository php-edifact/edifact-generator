# You can find the new timestamped tags here: https://hub.docker.com/r/gitpod/workspace-full/tags
FROM gitpod/workspace-full:latest

# Change your version here
RUN sudo update-alternatives --set php $(which php7.4)