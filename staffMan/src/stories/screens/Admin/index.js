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
} from "native-base";

import styles from "./styles";

class Admin extends React.Component {
  render() {
    return (
      <Container style={styles.container}>
        <Header>
          <Left>
            <Button transparent>
              <Icon
                active
                name="menu"
                onPress={() => this.props.navigation.openDrawer() }
              />
            </Button>
          </Left>
          <Body>
            <Title>Admin Panel</Title>
          </Body>
          <Right />
        </Header>
        <Content>
          <Text>Admin Panel</Text>
        </Content>
      </Container>
    );
  }
}

export default Admin;
