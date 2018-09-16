import * as React from "react";
import { Container, Content, Header, Body, Title, Button, Text, View, Icon } from "native-base";
//import styles from "./styles";

class Login extends React.Component {
	render() {
		return (
			<Container>
				<Header style={{ height: 250 }}>
					<Body style={{ alignItems: "center" }}>
						<Icon name="flash" style={{ fontSize: 104,  }} />
						<Title style={{fontSize: 30}}>StaffMan!</Title>
						<Text style={{ color: "#FFF", opacity: 0.5, paddingBottom: 20 }}>
							version 1.0.0
						</Text>
					</Body>
				</Header>
				<Content style={{marginTop: 30}}>
					{this.props.loginForm}
					<View padder>
						<Button block onPress={() => this.props.onLogin()}>
							<Text>Login</Text>
						</Button>
					</View>
				</Content>
				
			</Container>
		);
	}
}

export default Login;
