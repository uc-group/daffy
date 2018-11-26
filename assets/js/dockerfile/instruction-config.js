import MultipleSourceDestination from './components/instructions/MultipleSourceDestination';
import SourceDestination from './components/instructions/SourceDestination';
import TextArea from './components/instructions/TextArea';
import Executable from './components/instructions/Executable';
import KeyValue from './components/instructions/KeyValue';
import Values from './components/instructions/Values';
import Port from './components/instructions/Port';
import ArrayString from './components/instructions/ArrayString';
import String from './components/instructions/String';

export default {
    add: {
        label: 'ADD',
        component: SourceDestination
    },
    addMultipleSources: {
        label: 'ADD (multiple sources)',
        component: MultipleSourceDestination
    },
    run: {
        label: 'RUN',
        component: TextArea,
        props: {
            propertyName: 'commands'
        }
    },
    copy: {
        label: 'COPY',
        component: SourceDestination
    },
    copyMultipleSources: {
        label: 'COPY (multiple sources)',
        component: MultipleSourceDestination
    },
    cmd: {
        label: 'CMD',
        component: Executable
    },
    singleLabel: {
        label: 'LABEL',
        component: KeyValue
    },
    multiLabel: {
        label: 'LABEL (mutilple key-value pairs)',
        component: Values
    },
    expose: {
        label: 'EXPOSE',
        component: Port
    },
    singleEnv: {
        label: 'ENV',
        component: KeyValue
    },
    multiEnv: {
        label: 'ENV (multiple key-value pairs)',
        component: Values
    },
    entrypoint: {
        label: 'ENTRYPOINT',
        component: Executable
    },
    volume: {
        label: 'VOLUME',
        component: ArrayString,
        props: {
            propertyName: 'volumes'
        }
    },
    user: {
        label: 'USER',
        component: KeyValue,
        props: {
            keyName: 'user',
            keyLabel: 'User',
            valueName: 'group',
            valueLabel: 'Group'
        }
    },
    workdir: {
        label: 'WORKDIR',
        component: String,
        props: {
            propertyName: 'path',
            label: 'Path'
        }
    },
    arg: {
        label: 'ARG',
        component: KeyValue,
        props: {
            valueName: 'default'
        }
    }
}
