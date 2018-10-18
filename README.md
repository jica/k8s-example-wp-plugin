# Kubernetes RBAC Exercise: Plugin

> NOTE: This is meant to be used as a training exercise (it is incomplete in purpose)

Example plugin to test RBAC, used for trainings. 

The code is not complete. You need to fill two parts:

  - The URI of the K8s API to get the information about the current deployment (which name is stored in the `WP_K8S_PLUGIN_DEPLOYMENT_NAME` env var)
  - The path to the service account token file

It is writing information in the error log so you can also check in your web server logs.


This is a simple widget that shows the number of replicas of the deployment.

- Backend view:
![Backend](https://raw.githubusercontent.com/javsalgar/k8s-example-wp-plugin/master/images/Backend.png)

- Frontend view:
![Frontend](https://raw.githubusercontent.com/javsalgar/k8s-example-wp-plugin/master/images/Frontend.png)
