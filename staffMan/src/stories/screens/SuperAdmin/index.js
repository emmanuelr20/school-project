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
} from "native-base";

import styles from "./styles";

class SuperAdmin extends React.Component{
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
            <Title >Super Admin Panel</Title>
          </Body>
        </Header>
        <Content>
          <Text>Super Admin Panel</Text>
        </Content>
      </Container>
    );
  }
}

export default SuperAdmin;
