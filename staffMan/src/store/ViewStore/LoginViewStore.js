import { observable, action } from "mobx";
import {AsyncStorage} from "react-native";

const url = "http://10.0.2.2:8000/";

class LoginStore {
  @observable credentials = "";
  @observable password = "";
  @observable isValid = false;
  @observable credentialsError = "";
  @observable passwordError = "";

  @action
  credentialsOnChange(cred) {
    this.credentials = cred;
    this.validateCredentials();
  }


  @action
  credentialsOnChange(cred) {
    this.credentials = cred;
    this.validateCredentials();
  }

  @action
  validateCredentials() {
    const email = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(this.credentials) ? true : undefined;
    const staffId = /^[A-Z]{3}[0-9]{7}$/i.test(this.credentials) ? true : undefined;
    const required = this.credentials ? undefined : "Required";
    this.credentialsError = required
      ? required : email ? 
      undefined : staffId ? 
      undefined : "Invalid login credentials";
  }

  @action
  passwordOnChange(pwd) {
    this.password = pwd;
    this.validatePassword();
  }

  @action
  validatePassword() {
    const minLength = this.password.length < 8 ? "Must be 8 characters or more" : undefined;
    const required = this.password ? undefined : "Required";
    this.passwordError = required ? required : minLength;
  }

  @action
  validateForm() {
    if (this.credentialsError === undefined && this.passwordError === undefined) {
      this.isValid = true;
    }
  }

  @action
  clearStore() {
    this.credentials = "";
    this.isValid = false;
    this.credentialsError = "";
    this.password = "";
    this.passwordError = "";
  }

  @action
  fetchToken(navigator, errAlert, rootStore, resetAction){
    const data = { password: this.password, credential: this.credentials };
    const options = { 
      method: "POST", 
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data) 
    };
    fetch(url + "login", options)
      .then((res)=>res.json())
      .then((response) => {
        if (response.status === "success"){
          AsyncStorage.setItem("@auth:token", response.token , (error) => {
            if (error) {return errAlert("error: " + error);}
            // store user to root store
            rootStore.login(response.token, response.user);
            this.clearStore();
            return navigator.dispatch(resetAction);
          });
        } else {
          return errAlert(response.message);
        }
      })
      .catch(err => errAlert("err" + err));
  }
}

export default LoginStore;
