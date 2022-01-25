// This our own Vue Plugin

import "./policies.js";

export default {
  // called by Vue.use(authorize)
  //
  install(Vue, options) {

    /*
    There may be data/utilities you’d like to use in many components,
    but you don’t want to pollute the global scope.

    In these cases, you can make them available to each Vue instance by defining
    them on the prototype:
    */

   // Vue.prototype.$appName
   // In this case we create authorize() function that takes policy and model as defined in policies.js
    Vue.prototype.authorize = function(policy, model) {
      if (!window.Auth.signedIn) {
        return false;
      }

      if (typeof policy === "string" && typeof model === "object") {
        const user = window.Auth.user;

        return policies[policy](user, model);
      }
    };
    Vue.prototype.signedIn = window.Auth.signedIn;
  },
};
