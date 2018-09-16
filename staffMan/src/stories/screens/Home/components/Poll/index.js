import * as React from "react";
import Moment from "moment";
import { observer, inject } from "mobx-react/native";
import {
  View, Text, Spinner
} from "native-base";

import { FlatList, StyleSheet, TouchableOpacity, Image } from "react-native";
import { List } from "react-native-elements";

import Style from "../../../../../theme/variables/platform.js";

// import styles from "./styles";

const url = "http://10.0.2.2:8000/";

@inject("rootStore")
@observer
class Poll extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      polls: [],
      isLoading: false,
      page: 0,
      last_loaded: Date.now(),
      loaded: Date.now(),
      topLoader: false,
      new_polls_count: 0 ,
      bottomLoader: false
    };
  }

  fetchLatestPolls() {
    this.setState({ topLoader: true });
    const options = { 
        method: "POST", 
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          api_token: this.props.rootStore.auth_token
        },
        body: JSON.stringify({
          last_loaded: this.state.last_loaded,
        }) 
      };
      fetch(url + "/polls/latest", options)
            .then((data)=>data.json())
            .then((data) => {
                if (data.status === "success"){
                   this.setState(state => ({ polls: [...data.polls, ...state.polls]}), 
                    () => this.setState({ topLoader: false, new_polls_count: 0, last_loaded: data.loaded_at })
                    );
                    console.log(this.state);
                   } else {
                  this.setState({ topLoader: false });
                }
            })
            .catch(err => { if (err.message === "Unexpected token U in JSON at position 0")
                                      { this.props.rootStore.logout(this.props.navigation); }
                            else {
                              console.log(err.message);
                              this.setState({ topLoader: false });
                            }
                          } );
  }

  fetchPolls() {
      this.setState({ bottomLoader: true });
      const options = { 
        method: "POST", 
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          api_token: this.props.rootStore.auth_token,
        },
        body: JSON.stringify({
          loaded: this.state.loaded,
          last_loaded: this.state.last_loaded,
        }) 
      };
      this.setState(state => ({ page: state.page += 1 }), () =>
        fetch(url + "/polls?page=" + this.state.page, options)
            .then((data)=>data.json())
            .then((data) => {
                if (data.status === "success"){
                   this.setState(state => ({ polls: [...state.polls, ...data.polls.data], new_polls_count: parseInt(data.new_polls_count, 10) }), 
                    () => this.setState({ bottomLoader: false, isLoading: false })
                    );
                   } else {
                  this.setState({ bottomLoader: false, isLoading: false });
                }
            })
            .catch(err => { if (err.message === "Unexpected token U in JSON at position 0")
                                      { this.props.rootStore.logout(this.props.navigation); }} )
      );
  }

  componentWillMount() {
    this.setState({ isLoading: true });
    this.fetchPolls();

  }

  _renderListItem(poll) { 
    return (
      <TouchableOpacity onPress={() => this.props.navigation.navigate({ routeName: "PollView", key: poll.id, params: { poll_id: poll.id } })}>
        <View style = {styles.listItemContainer}>
          <View style= {styles.iconContainer}>
            <Image source={{uri: poll.image_url ? url + "images/" + poll.image_url : null}} style={{ height: 80, width: 80 }} resizeMode="contain" />
          </View>
          <View style = {styles.callerDetailsContainer}>
            <View>
                <Text style={{fontWeight: "bold", marginBottom: 5, color: Style.sTabBarActiveTextColor }}>{poll.title}</Text>
            </View>
            <View >
              <Text numberOfLines={2} style={{flex: 1, color: "#777" }}>{poll.body}</Text>
            </View>
            <View>
                <Text style={{ color: "#D2AE6D", fontSize: 12, textAlign: "right", paddingRight: 20 }}>{ Moment(poll.created_at).utcOffset(60).fromNow() }</Text>
            </View>
          </View>
        </View>
      </TouchableOpacity>
      );
  }

  _renderListFooter() {
    if (this.state.bottomLoader) {
      return (<Spinner style={ styles.itemSpinner}/>);
    } else { 
      return (<Text/>);
    }
  }

  _renderListHeader() {
   if (this.state.new_polls_count > 0) { 
      return (<TouchableOpacity onPress={() => this.fetchLatestPolls()}>
                <View style={{ padding: 10,  backgroundColor:  "#D2AE6D" }}>
                  <Text style={{ color: "white" }}> click here to load { this.state.new_polls_count } new polls</Text>
                </View>
              </TouchableOpacity>);
    } else { return (<View/>); }
  }

  render() {
    if (this.state.isLoading) {
      return <Spinner style={{ flex: 1 }}/>;
    }
    return (
        <View style={{ backgroundColor: "#f6f6f6", flex: 1, padding: 10, paddingTop: 0 }}>
          <List>
            <FlatList
                data={this.state.polls}
                onRefresh={() => this.fetchLatestPosts()}
                refreshing={this.state.topLoader}
                onEndReached={() => this.fetchPolls()}
                onEndReachedThreshold={1}
                keyExtractor={ item => "key-" + item.id}
                renderItem={({item}) => this._renderListItem(item)}
                ItemSeparatorComponent={()=>(
                  <View style={{ backgroundColor: "#f6f6f6", marginTop: 10 }} ><Text/></View>
                )}
                ListFooterComponent={()=> this._renderListFooter() }
                ListHeaderComponent={()=> this._renderListHeader() }
              />
          </List>
        </View>
      );
  }
}

export default Poll;

const styles = StyleSheet.create({
  listItemContainer: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
    padding: 10
  },
  iconContainer: {
    flex: 2,
    alignItems: "flex-start"
  },
  callerDetailsContainer: {
    flex: 7,
    justifyContent: "center"
  },
  itemSpinner: { 
    height: 50, 
    alignItems:  "center", 
    justifyContent: "center"}
});
