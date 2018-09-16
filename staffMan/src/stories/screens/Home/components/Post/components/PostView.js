//@flow
import * as React from "react";
import {
  Header,
  Title,
  Text,
  Button,
  Icon,
  Left,
  Right,
  Spinner,
  View, 
  Input,
  Item,
} from "native-base";
import { Image, FlatList } from "react-native";
import { ScrollView } from "react-native";
import { observer, inject } from "mobx-react/native";
import styles from "./styles";
import Style from "../../../../../../theme/variables/platform.js";
// import ImageSlider from "react-native-image-slider";
import Moment from "moment";


const url = "http://10.0.2.2:8000/";

@inject("rootStore")
@observer
class PostView extends React.Component {
	constructor(props){
		super(props);
		this.state = {
			post: null,
			status: "loading",
			comments: [],
			loading: true,
			postError: false,
			comment: "",
			commentLoader: false,
			page: 2,
			loaded: Date.now(),
			listEndReached: false
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
		fetch(url + "/posts/" + this.props.post_id, options)
				.then((data)=>data.json())
				.then((data) => {
					if (data.status === "success"){
						this.setState(
							state => ({ post: data.post, comments: data.post.comments }),
							() => this.setState({ loading: false })
						);
					} else {
						this.setState({ loading: false, status: "failed" });
					}
				})
				.catch(err => { if (err.message === "Unexpected token U in JSON at position 0")
                                      { this.props.rootStore.logout(this.props.navigation); }
                            else {
                              console.log(err.message);
                              this.setState({ loading: false });
                            }
                          } );
	}

	postComment() {
		if (this.state.comment) {
			const options = { 
				method: "POST", 
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
					api_token: this.props.rootStore.auth_token
				},
				body: JSON.stringify({
					comment: this.state.comment,
				})
			};
			fetch(url + "comments/post/create/" + this.state.post.id , options)
				.then((data)=>data.json())
				.then((data) => {
					if (data.status === "success"){
						this.setState(state => (
							{ comments: [ data.comment, ...state.comments], comment: ""}), 
							() => this.setState({ commentLoader: false })
						);
					} else {
						this.setState({ commentLoader: false });
					}
				})
				.catch(err => { 
					if (err.message === "Unexpected token U in JSON at position 0") { 
						this.props.rootStore.logout(this.props.navigation); 
					} else {
						console.log(err.message);
						this.setState({ commentLoader: false });
					}
				});
		} else {
			this.setState({postError: true});
		}
	}

	setComment(comment) {
		this.setState({comment});
		return;
	}

	_renderComment(comment){
		return (<View style={{ backgroundColor: "#fbfbfb", padding: 10, margin: 2 }} key={comment.id}>
		<Text style={{ color: Style.sTabBarActiveTextColor}}>
			{ comment.user.first_name + " " + comment.user.last_name + "(" + comment.user.staff_id + ")"}
		</Text>
		<Text style={{ marginBottom: 5 }}>
			{ comment.body }
		</Text>
		<Text style={{ fontSize: 12, color: "#777", marginBottom: 5, marginLeft: "auto" }}>
			{ Moment(comment.created_at).utcOffset(60).fromNow() }
		</Text>
	</View>);
	}
	_renderListFooter() {
		if (this.state.commentLoader) {
			return (<Spinner style={ styles.itemSpinner}/>);
		} else { 
			return (<View/>);
		}
	}

	fetchComments() {
		if (this.state.listEndReached) { return; }
		this.setState({ commentLoader: true });
		const options = { 
			method: "POST", 
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
				api_token: this.props.rootStore.auth_token,
			},
			body: JSON.stringify({
				loaded: this.state.loaded
			}) 
		};
		fetch(url + "/comments/post/list/" + this.state.post.id + "?page=" + this.state.page, options)
			.then((data)=>data.json())
			.then((data) => {
				data.comments.last_page <= this.state.page ? this.setState({ listEndReached: true }) : null;
				this.setState(state => ({ comments: [ ...state.comments, ...data.comments.data ], page: state.page += 1 }), 
					() => this.setState({ commentLoader: false})
				);
			})
			.catch(err => { 
				if (err.message === "Unexpected token U in JSON at position 0"){
					this.props.rootStore.logout(this.props.navigation); 
				} else {
					alert(err.message);
				}} );
	}
	
	render() {
		let post = this.state.post;
		return (
			<ScrollView style={styles.mainContainer}>
				<View style={styles.headerContainer}>
				<Header>
					<Left style={{ flexDirection: "row", alignItems: "center" }}>
						<Button transparent onPress={() => this.props.navigation.goBack()}>
							<Icon name="ios-arrow-back" />
						</Button>
						<Title style={{ paddingLeft: 12 }}>{post ? post.title : "post"}</Title>
					</Left>
					<Right/>
				</Header>
				</View>
				<View style={styles.contentContainer}>
					{ this.state.loading 
						? <Spinner style={{ flex: 1 }}/>
						: 
						<View style={{ backgroundColor: "#f0f0f0", flex: 1, padding: 15 }}>
							<View >
								<Image 
									source = {{uri: post.image_url ? url + "images/" + post.image_url : null}}
									style={{ width: "100%", height: 350}}
									resizeMode="cover"
									/>
							</View>
							<View style={{ padding: 15, backgroundColor: "#fff", marginTop: 5}}>							
								<Text style={{ fontWeight: "bold", fontSize: 30, marginBottom: 7, color: Style.sTabBarActiveTextColor }}>{post.title}</Text>
								<Text style={{fontSize: 12, color: "#555"}}>
									posted by
									<Text style={{fontSize: 18, color: Style.brandPrimary}}>{" " + post.user.first_name + " " + post.user.last_name }</Text>
								</Text>
								<Text style={{ fontSize: 15, color: "#555", marginBottom: 7 }}>
									{ Moment(post.created_at).utcOffset(60).fromNow() }
								</Text>
								
								{
									this.props.rootStore.user.is_super_admin || this.props.rootStore.user.id === post.user.id
									? <View style={{ flexDirection: "row", alignItems: "center", marginBottom: 10 }}>
										<Button  style={{ backgroundColor: "#eee", marginRight: 10,}}>
											<Icon type="FontAwesome" name="trash" style={{color: "red"}}/>
										</Button>
										<Button >
											<Icon type="FontAwesome" name="edit" />
										</Button>
									</View>	
									: <View/>
								}
								<Text style={{ textAlign: "justify" }}>{post.body}</Text>
							</View>
							<View style={{ padding: 15, backgroundColor: "#fff", marginTop: 10}}>
								<Text style={{ fontWeight: "bold", fontSize: 30, color: "#555" }}>Comments</Text>
								<Item error={this.state.postError} style={{ backgroundColor: "#f3f3f3", marginBottom: 10 }} >
									<Input
										placeholder="add comment"
										ref={c => this.commentInput = c}
										value={this.state.comment}
										onChangeText={e => this.setComment(e)}
										multilne={true}
										numberOfLines={4}
									/>
								</Item>
								<Button style={{ marginLeft: "auto" , marginBottom: 12 }} onPress={() => this.postComment()}>
									<Text>Post</Text>
								</Button>
								<View style={{ padding: 2, backgroundColor: "#eee"}}>
									{
										this.state.comments.length === 0 
											? <View><Text style={{ color: "#888", fontSize: 20 }}> No comments yet! </Text></View>
											: <FlatList
												data={this.state.comments}
												onEndReached={() => this.fetchComments()}
												onEndReachedThreshold={1}
												keyExtractor={ item => "key-" + item.id}
												renderItem={({item}) => this._renderComment(item)}
												ListFooterComponent={()=> this._renderListFooter() }
											/>
									}
								</View>
							</View>
						</View>
					}
				</View>
				
			</ScrollView>
		);
	}
}

export default PostView;
