import '../setup';
import { render, fireEvent } from '@testing-library/vue';
import Details from '../../../resources/js/components/Details.vue';
import Vue from 'vue';
import { expect } from 'chai';

const pageFactory = (name, template) => {
  const component = Vue.component(name, { template: template || '<div />' });
  return { name, component };
};

describe('Details.vue', () => {
  it('shows its title', () => {
    const props = { value: {}, title: 'Test title', pages: [] };

    const { queryByText } = render(Details, { props });

    expect(queryByText('Test title')).to.be.ok;
  });

  it('shows its page names', () => {
    const props = {
      value: {},
      title: '',
      pages: [
        pageFactory('foo-bar'),
        pageFactory('baz')
      ]
    };

    const { queryByText } = render(Details, { props });

    expect(queryByText('foo bar')).to.be.ok;
    expect(queryByText('baz')).to.be.ok;
  });

  it('marks the first page as active by default', () => {
    const props = {
      value: {},
      title: '',
      pages: [
        pageFactory('foo-bar'),
        pageFactory('baz')
      ]
    };

    const { getByText } = render(Details, { props });
    
    const firstLink = getByText('foo bar');
    const secondLink = getByText('baz');

    expect(firstLink).to.have.class('text-gray-200');
    expect(firstLink).to.have.class('bg-red-700');
    expect(firstLink).to.have.class('hover:text-gray-200');
    expect(secondLink).to.not.have.class('text-gray-200');
    expect(secondLink).to.not.have.class('bg-red-700');
    expect(secondLink).to.not.have.class('hover:text-gray-200');
  });

  it('activates a page when clicked', async () => {
    const props = {
      value: {},
      title: '',
      pages: [
        pageFactory('foo-bar', '<div>Foo Bar</div>'),
        pageFactory('baz', '<div>Baz</div>')
      ]
    };

    const { queryByText, getByText } = render(Details, { props });
    expect(queryByText('Foo Bar')).to.be.ok;
    expect(queryByText('Baz')).to.be.null;

    const firstLink = getByText('foo bar');
    const secondLink = getByText('baz');
    await fireEvent.click(secondLink);

    expect(firstLink).to.not.have.class('text-gray-200');
    expect(firstLink).to.not.have.class('bg-red-700');
    expect(firstLink).to.not.have.class('hover:text-gray-200');
    expect(secondLink).to.have.class('text-gray-200');
    expect(secondLink).to.have.class('bg-red-700');
    expect(secondLink).to.have.class('hover:text-gray-200');
    expect(queryByText('Foo Bar')).to.be.null;
    expect(queryByText('Baz')).to.be.ok;
  });
});
