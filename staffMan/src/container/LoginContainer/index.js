// @flow
import * as React from "react";
import {AsyncStorage} from "react-native";
import { Item, Input, Icon, Form, Toast, Spinner } from "native-base";
import { observer, inject } from "mobx-react/native";
import { NavigationActions, StackActions } from "react-navigation";

import Login from "../../stories/screens/Login";

const url = "http://10.0.2.2:8000/";

const resetAction = StackActions.reset({
	index: 0,
	actions: [NavigationActions.navigate({ routeName: "Drawer" })],
});

@inject("loginForm")
@inject("rootStore")
@observer
export default class LoginContainer extends React.Component {
	constructor(props) {
		super(props);
		this.state = { isLoading: false };
	}

	login() {
		this.props.loginForm.validateForm();
		if (this.props.loginForm.isValid) {
			this.props.loginForm.fetchToken(this.props.navigation, this.errorAlert, this.props.rootStore, resetAction);
			this.props.loginForm.clearStore();
		} else {
			this.errorAlert("Enter Valid Email & password!");
		}
	}

	errorAlert(msg) {
		Toast.show({
			text: msg,
			duration: 2000,
			position: "top",
			textStyle: { textAlign: "center" },
		});
	}

	checkAuth() {
		this.setState({ isLoading: true });
		if (this.props.rootStore.auth_token) {
			return this.props.navigation.dispatch(resetAction);
		}
		AsyncStorage.getItem("@auth:token", (error, api_token) => {
			if (error) { return this.errorAlert("error: " + error); }
			if (api_token) {
				const options = {
					method: "POST",
					headers: {
						Accept: "application/json",
						"Content-Type": "application/json"
					},
					body: JSON.stringify({ api_token })
				};
				fetch(url + "/token/user", options)
					.then((data)=>data.json())
					.then((data) => {
						if (data.status === "success"){
							this.props.rootStore.login(data.token, data.user);
							this.setState({ isLoading: false });
							return this.props.navigation.dispatch(resetAction);
						} else {
							this.setState({ isLoading: false });
							AsyncStorage.removeItem("@auth:token");
						}
					})
					.catch(err => {
						AsyncStorage.removeItem("@auth:token");
						this.setState({isLoading: false});
						console.log(err);
					});
			} else {
				this.setState({ isLoading: false });
			}
			return false;
		});
	}

	componentWillMount() {
		this.checkAuth();
	}

	render() {
		const form = this.props.loginForm;
		const Fields = (
			<Form>
				<Item error={form.credentialsError ? true : false}>
					<Icon active name="person" />
					<Input
						placeholder="Email or Staff ID Number"
						keyboardType="email-address"
						ref={c => (this.emailInput = c)}
						value={form.email}
						onBlur={() => form.validateCredentials()}
						onChangeText={e => form.credentialsOnChange(e)}
					/>
				</Item>
				<Item error={form.passwordError ? true : false}>
					<Icon active name="unlock" />
					<Input
						placeholder="Password"
						ref={c => (this.pwdinput = c)}
						value={form.password}
						onBlur={() => form.validatePassword()}
						onChangeText={e => form.passwordOnChange(e)}
						secureTextEntry={true}
					/>
				</Item>
			</Form>
		);
		if (this.state.isLoading) { return <Spinner style={{ flex: 1 }}/>; }
		return <Login navigation={this.props.navigation} loginForm={Fields} onLogin={() => this.login()} />;
	}
}
