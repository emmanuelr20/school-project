import * as React from "react";
import {
  Header,
  Title,
  Button,
  Left,
  Right,
  View
} from "native-base";

import Icon from "react-native-vector-icons/MaterialIcons";
import ScrollableTabView from "react-native-scrollable-tab-view";

import Post from "../../../container/HomeContainer/components/PostContainer";
import Poll from "../../../container/HomeContainer/components/PollContainer";
import Message from "../../../container/HomeContainer/components/MessageContainer";

import Style from "../../../theme/variables/platform.js";

import styles from "./styles";

class Home extends React.Component {

  constructor(props) {
    super(props);
    this.state = { selected: 0 };
  }

  handleChangeTab(index) {
    this.setState({selected: index.i});
  }

  _getIcon() {
    if (this.state.selected === 0) {
      return "mail";
    } else if (this.state.selected === 1) {
      return "poll";
    } else {
      return "chat";
    }
  }


  render() {
    return (
      <View style={styles.mainContainer}>
          <View style={styles.headerContainer}>
          <Header>
          <Left style={{ flexDirection: "row", alignItems: "center" }}>
            <Button transparent>
              <Icon
                name="menu"
                size={30} color="#fff" style={{ padding: 5 }}
                onPress={ () => this.props.navigation.openDrawer() }
              />
            </Button>
            <Title style={{ paddingLeft: 12 }}>Home</Title>
          </Left>
          <Right>
            <Icon name="search" size={20} color="#fff" style={{ padding: 5 }}/>
            <Icon name={ this._getIcon() } size={20} color="#fff" style={{ padding: 5 }}/>
          </Right>
        </Header>
          </View>
          <View style={styles.contentContainer}>
            <ScrollableTabView
              tabBarUnderlineColor="#fff"
              tabBarUnderlineStyle={{backgroundColor: Style.tabActiveBgColor}}
              tabBarBackgroundColor ="#D2AE6D"
              tabBarActiveTextColor={Style.tabActiveBgColor}
              tabBarInactiveTextColor="#fff"
              onChangeTab={(index) => this.handleChangeTab(index)}
              >
              <Post {...this.props} tabLabel="NEWSLETTER"/>
              <Poll {...this.props} tabLabel="POLLS"/>
              <Message {...this.props} tabLabel="MESSENGERS"/>
            </ScrollableTabView>
          </View>
        </View>
    );
  }
}

export default Home;


