//@flow
import * as React from "react";
import {
  Container,
  Header,
  Title,
  Content,
  Text,
  Button,
  Icon,
  Left,
  Body,
  Right,
  Spinner,
} from "native-base";
import { observer, inject } from "mobx-react/native";
import styles from "./styles";

const url = "http://10.0.2.2:8000/";

@inject("rootStore")
@observer
class PollView extends React.Component {
	constructor(props){
		super(props);
		this.state = {
			poll: null,
			status: "loading",
			comments: [],
			loading: true
		};
	}
	componentDidMount() {
		const options = {
			method: "GET",
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
				api_token: this.props.rootStore.auth_token
			},
		};
		console.log(this.props.rootStore.auth_token);
		fetch(url + "/polls/" + this.props.poll_id, options)
				.then((data)=>data.json())
				.then((data) => {
					if (data.status === "success"){
						console.log(data);
						this.setState(state => ({ poll: data.poll, comments: data.poll.comments }),
						() => this.setState({ loading: false })
						);
					} else {
						this.setState({ loading: false, status: "failed" });
					}
				})
				.catch(err => console.log(err.message) );
	}
	render() {
		
		return (
		<Container style={styles.container}>
			<Header>
			<Left>
				<Button transparent>
				<Icon
					active
					name="menu"
					onPress={() => this.props.navigation.openDrawer()}
				/>
				</Button>
			</Left>
			<Body>
				<Title>Poll View: </Title>
			</Body>
			<Right />
			</Header>
			<Content>
				{ this.state.loading 
					? <Spinner style={{ flex: 1, alignItems: "center", justifyContent: "center" }}/>
					: <Text>Poll {this.props.poll_id}</Text>
				}
			</Content>
		</Container>
		);
	}
}

export default PollView;
