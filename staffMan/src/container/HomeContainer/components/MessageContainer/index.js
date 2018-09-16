// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import { Text } from "native-base";

export interface Props {
	navigation: any,
	mainStore: any,
}
export interface State {}

@inject("mainStore")
@observer
export default class MessageContainer extends React.Component<Props, State> {
	
	render() {
		return <Text>Message</Text>
	}
}