import { Hero } from './components/Hero';
import { Schedules } from './components/Schedules';
import { Features } from './components/Features';
import { Fleet } from './components/Fleet';
import { Charter } from './components/Charter';
import { Contact } from './components/Contact';
import { CtaBanner } from './components/CtaBanner';

export function Home() {
  return (
    <main>
      <Hero />
      <Features />
      <Schedules />
      <Fleet />
      <Charter />
      <Contact />
      <CtaBanner />
    </main>
  );
}